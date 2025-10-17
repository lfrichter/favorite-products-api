# 9. Orquestração de Lógica de Negócio com Serviços Dedicados e DTOs

**Status:** Aceito

**Contexto:**
A lógica para obter a lista de produtos favoritos de um usuário estava implementada diretamente no `FavoriteProductController`. Essa abordagem apresentava diversos problemas arquiteturais:

1.  **Ineficiência:** Causava um problema de N+1 requisições HTTP, onde cada produto favorito gerava uma nova chamada para a API externa.
2.  **Alto Acoplamento:** O controller estava diretamente acoplado à estrutura de dados (arrays associativos) retornada pela `FakeStoreAPI`.
3.  **Baixa Coesão:** O controller acumulava responsabilidades de orquestração de dados, além de sua função principal de gerenciar a requisição/resposta HTTP.
4.  **Falta de Reutilização:** A lógica não podia ser facilmente reutilizada em outras partes da aplicação, como um job ou comando Artisan.

**Decisão:**
Para solucionar esses problemas e alinhar a aplicação com os princípios de Clean Architecture e Domain-Driven Design, foram adotadas duas medidas:

1.  **Criação de um Serviço de Orquestração:** A lógica de negócio foi extraída para um serviço dedicado, o `FavoriteProductService`. Este serviço agora é o único responsável por orquestrar a busca dos IDs dos favoritos e solicitar os detalhes completos dos produtos ao `ProductServiceContract`.
2.  **Introdução de Data Transfer Objects (DTOs):** Foi criado um `ProductDTO` tipado e imutável. O `FavoriteProductService` agora mapeia os dados brutos recebidos da API externa para uma coleção de objetos `ProductDTO`. Isso garante que o resto da aplicação opere com uma estrutura de dados canônica, segura e desacoplada de fontes externas.

**Consequências:**
* **Positivas:**
    * **Eliminação do N+1:** A nova camada de serviço permite a implementação de buscas em lote (`findProductsByIds`), reduzindo N chamadas HTTP para uma ou poucas, melhorando drasticamente o desempenho.
    * **Separação Clara de Responsabilidades (SRP):** O controller agora é um "adaptador" limpo, o serviço orquestra a lógica de negócio e o DTO representa os dados de domínio, cada um com uma única responsabilidade.
    * **Desacoplamento da Fonte Externa:** A aplicação interna passa a depender do `ProductDTO`, não da estrutura da `FakeStoreAPI`. Mudanças na API externa só exigem alterações no mapeamento dentro do serviço.
    * **Segurança de Tipos (Type Safety):** O uso de DTOs tipados elimina uma classe inteira de erros de runtime (chaves de array inexistentes, tipos de dados incorretos) e habilita autocomplete e refatoração segura na IDE.
    * **Alta Testabilidade:** Cada componente (controller, serviço, DTO) pode ser testado de forma isolada, rápida e confiável.
    * **Reutilização e Escalabilidade:** A lógica de negócio encapsulada no serviço pode ser facilmente injetada e reutilizada em qualquer lugar da aplicação, e a estrutura com DTOs cria uma base sólida para evoluções como caching e suporte a múltiplas fontes de dados.

* **Negativas:**
    * A introdução de mais uma camada de serviço e um DTO aumenta ligeiramente a quantidade de código e a complexidade inicial. Este é um trade-off deliberado e positivo em favor da manutenibilidade, desempenho e robustez do sistema a longo prazo.