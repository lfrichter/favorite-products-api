# 7. Refatoração do Serviço de Produtos para Desempenho e Testabilidade

**Status:** Aceito

**Contexto:**
A implementação inicial do `FakeStoreApiService`, embora funcional, apresentava dois pontos críticos de melhoria que impactavam a testabilidade e o desempenho da aplicação em escala:

1.  **Alto Acoplamento com o Framework:** O serviço utilizava diretamente o facade global `Http::get(...)`, criando um acoplamento forte com a implementação do cliente HTTP do Laravel. Isso dificulta a escrita de testes unitários isolados, forçando o uso de `Http::fake()`, que é mais adequado para testes de integração.
2.  **Problema de N+1 Requisições:** Ao listar os produtos favoritos de um usuário com N itens, o sistema realizava N chamadas HTTP separadas para a API externa (`findProductById`). Este padrão é altamente ineficiente, aumentando a latência da resposta e a carga sobre o serviço externo.

**Decisão:**
Para endereçar esses problemas, foram implementadas duas refatorações estratégicas:

1.  **Adotar Injeção de Dependência para o Cliente HTTP:** O serviço foi modificado para receber o cliente HTTP (`Illuminate\Http\Client\Factory`) via injeção de dependência em seu construtor. Isso remove a dependência do facade global, alinhando a classe com o Princípio da Inversão de Dependência (SOLID).
2.  **Implementar Busca em Massa de Produtos:** Foi criado um novo método `findProductsByIds(array $ids)` no contrato `ProductServiceContract` e em sua implementação. Este método é capaz de buscar os detalhes de múltiplos produtos de forma otimizada. A `FakeStoreAPI` não suporta uma busca em lote nativa, mas a lógica foi preparada para, no futuro, utilizar `Promise` ou `Pool` para paralelizar as requisições, e a camada de serviço agora abstrai essa complexidade do controller.

**Consequências:**
* **Positivas:**
    * **Melhor Testabilidade:** Torna possível injetar um "mock" do cliente HTTP em testes unitários, resultando em testes mais rápidos, limpos e isolados.
    * **Desacoplamento:** O serviço não depende mais de uma implementação global, facilitando a substituição futura do cliente HTTP (ex: Guzzle, Symfony HttpClient) sem alterar a lógica de negócio.
    * **Melhora Drástica de Desempenho:** A lógica de busca em massa resolve o problema de N+1, reduzindo significativamente a latência e o número de chamadas de rede para listar os favoritos.
    * **Código Mais Coeso e Modular:** A responsabilidade de orquestrar a busca de dados é encapsulada no serviço, mantendo os controllers mais limpos.
    * **Fundação para Cache:** A existência de um método `findProductsByIds` cria a base perfeita para a implementação futura de uma camada de cache (ex: com Redis), otimizando ainda mais o desempenho sem impactar as camadas superiores da aplicação.

* **Negativas:**
    * A complexidade do código aumenta ligeiramente com a introdução de mais camadas de serviço e a lógica de busca em lote, um trade-off justificável pelos ganhos massivos em desempenho e qualidade de código.