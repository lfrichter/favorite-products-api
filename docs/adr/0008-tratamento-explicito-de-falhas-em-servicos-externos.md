# 8. Tratamento Explícito de Falhas em Serviços Externos

**Status:** Aceito

**Contexto:**
A implementação atual do serviço de integração com a `FakeStoreAPI` mascara as falhas de comunicação. Quando a API externa falha (por timeout, erro de servidor, etc.), o serviço registra um log e retorna um valor nulo ou um array vazio. O controller, sem conhecimento da falha, trata isso como um sucesso e retorna uma resposta `200 OK` com dados vazios para o cliente. Este comportamento de "falha silenciosa" é perigoso, pois pode levar a uma experiência de usuário confusa (ex: "meus favoritos desapareceram?"), dificultar o diagnóstico de problemas em produção e gerar inconsistências.

**Decisão:**
Para garantir a transparência e a robustez do sistema, as falhas de integração serão tratadas de forma explícita, seguindo os seguintes passos:

1.  **Lançar Exceções Customizadas:** Quando o serviço de integração detectar uma falha na comunicação com a API externa, ele deverá lançar uma exceção customizada (ex: `App\Exceptions\FakeStoreApiException`) em vez de retornar um valor nulo.
2.  **Tratamento Global de Exceções:** Essa exceção customizada será capturada e tratada globalmente no `App\Exceptions\Handler.php`. O handler será responsável por formatar uma resposta JSON adequada e retornar um status HTTP semanticamente correto, como `503 Service Unavailable`, informando ao cliente que o problema é temporário.

**Consequências:**
* **Positivas:**
    * **Transparência de Falhas:** Erros de integração não são mais mascarados. A aplicação reconhece e comunica explicitamente quando um serviço dependente está indisponível.
    * **Respostas HTTP Corretas:** A API retorna códigos de status apropriados (`503`), permitindo que os clientes (frontend, apps móveis) tratem o erro de forma inteligente, exibindo mensagens de feedback adequadas para o usuário.
    * **Depuração Facilitada:** A combinação de logs com o rastreamento de exceções torna o diagnóstico de problemas em produção muito mais rápido e eficaz.
    * **Testabilidade de Cenários de Falha:** Permite escrever testes que verificam especificamente o comportamento da aplicação quando uma dependência externa falha.
    * **Design Defensivo:** O código se torna mais resiliente ao assumir que falhas podem e vão acontecer, lidando com elas de forma controlada.
    * **Arquitetura Limpa:** A responsabilidade de detectar o erro fica no serviço, enquanto a responsabilidade de como responder ao erro fica centralizada no `Handler` global, mantendo os controllers limpos.

* **Negativas:**
    * Nenhuma consequência negativa significativa foi identificada. A leve complexidade adicional de criar e tratar uma exceção é um padrão de engenharia de software essencial para a construção de sistemas confiáveis.