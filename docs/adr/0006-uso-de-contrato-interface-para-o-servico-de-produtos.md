# 6. Uso de Contrato (Interface) para o Serviço de Produtos

**Status:** Aceito

**Contexto:**
Inicialmente, os controllers que precisavam de dados de produtos dependiam diretamente da classe concreta `FakeStoreApiService`. Essa abordagem cria um alto acoplamento (tight coupling) entre a camada de controle HTTP e a implementação específica do serviço de integração. Isso torna o sistema rígido, dificulta a substituição do serviço e complica a escrita de testes unitários rápidos e isolados, pois não é possível "mockar" a dependência facilmente.

**Decisão:**
Para resolver o alto acoplamento e seguir o Princípio da Inversão de Dependência (SOLID), foi introduzida uma abstração (uma `interface` do PHP) chamada `ProductServiceContract`.

1.  A interface `ProductServiceContract` define o contrato explícito que qualquer serviço de produto deve seguir (ex: `findProductById()`).
2.  A classe `FakeStoreApiService` foi refatorada para implementar esta interface.
3.  O Service Container do Laravel (no `AppServiceProvider`) é utilizado para fazer o "bind", instruindo a aplicação a injetar uma instância de `FakeStoreApiService` sempre que um `ProductServiceContract` for solicitado.

**Consequências:**
* **Positivas:**
    * **Baixo Acoplamento:** Os controllers agora dependem de uma abstração, não de uma implementação concreta. Isso torna o sistema flexível para futuras mudanças (ex: trocar a API externa por um banco de dados local) sem a necessidade de alterar o código dos controllers.
    * **Alta Testabilidade:** Facilita enormemente os testes unitários e de feature. É possível "mockar" a interface `ProductServiceContract` para simular respostas da API, tornando os testes mais rápidos, confiáveis e independentes de serviços externos.
    * **Coesão e SRP:** Reforça o Princípio da Responsabilidade Única. O controller apenas orquestra, enquanto a lógica de integração fica totalmente encapsulada no serviço, resultando em um código mais limpo e legível.
    * **Extensibilidade:** O sistema se torna mais fácil de estender. Novas fontes de produtos podem ser adicionadas criando novas classes que implementam o mesmo contrato, sem impactar o código existente.
    * **Clareza de Contrato:** A interface serve como uma documentação implícita, definindo claramente o que um "serviço de produto" é capaz de fazer.
    * **Alinhamento com SOLID:** A solução está diretamente alinhada com os princípios de Responsabilidade Única (S) e Inversão de Dependência (D).

* **Negativas:**
    * Introduz um pequeno aumento no número de arquivos (uma interface e uma entrada no Service Provider), o que é um trade-off mínimo em troca da imensa melhoria na manutenibilidade, flexibilidade e qualidade do código a longo prazo.