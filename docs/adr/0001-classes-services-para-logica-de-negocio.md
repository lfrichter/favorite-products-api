# 1. Uso de Classes de Serviço (Services) para Lógica de Negócio

**Status:** Aceito

**Contexto:**
A aplicação precisa se comunicar com uma API externa (`fakestoreapi.com`) para validar e buscar dados de produtos. Essa lógica de integração não pertence à camada de controle HTTP (Controllers), pois isso violaria o Princípio da Responsabilidade Única (SRP), gerando "Fat Controllers" e dificultando a testabilidade e a reutilização do código.

**Decisão:**
Toda a lógica de comunicação com a API externa será encapsulada em uma classe de serviço dedicada (`App\Services\FakeStoreApiService`). Os controllers que precisarem dessa funcionalidade irão recebê-la por meio de injeção de dependência.

**Consequências:**
* **Positivas:**
    * **Controllers Limpos:** Os controllers permanecem enxutos, focados apenas em gerenciar a requisição e a resposta HTTP.
    * **Alta Testabilidade:** É possível "mockar" facilmente o `FakeStoreApiService` nos testes de feature, permitindo testar os controllers de forma isolada e rápida.
    * **Reutilização de Código:** A lógica de integração pode ser facilmente reutilizada em outras partes do sistema (como Comandos Artisan ou Jobs) sem duplicação de código.
    * **Manutenção Simplificada:** Se a API externa mudar, as alterações ficam centralizadas em um único arquivo.
* **Negativas:**
    * Aumenta ligeiramente o número de arquivos no projeto, o que é um trade-off aceitável pela organização obtida.
