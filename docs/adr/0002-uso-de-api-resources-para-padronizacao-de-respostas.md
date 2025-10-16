# 2. Uso de API Resources para Padronização de Respostas

**Status:** Aceito

**Contexto:**
Uma API RESTful robusta precisa garantir um "contrato" de resposta estável e previsível para seus consumidores (como um frontend SPA ou um aplicativo móvel). Retornar modelos Eloquent diretamente pode expor dados sensíveis ou colunas internas do banco de dados (`password`, `remember_token`), além de criar um acoplamento forte entre a estrutura do banco de dados e a resposta da API. Qualquer alteração no banco de dados poderia quebrar os clientes da API de forma inesperada.

**Decisão:**
Todas as respostas da API que retornam dados de entidades (como `User`) serão formatadas através de [classes de `API Resource`](https://laravel.com/docs/11.x/eloquent-resources). Essas classes atuarão como uma camada de transformação, mapeando explicitamente os atributos do modelo para a estrutura JSON final da resposta.

**Consequências:**
* **Positivas:**
    * **Segurança:** Controle total sobre quais dados são expostos, prevenindo vazamento de informações sensíveis.
    * **Consistência:** Garante que a estrutura do JSON para uma entidade seja a mesma em todos os endpoints, criando um contrato de API confiável.
    * **Flexibilidade:** Permite formatar atributos, adicionar dados condicionais ou incluir relacionamentos de forma limpa e controlada.
    * **Desacoplamento:** A estrutura da resposta da API se torna independente da estrutura das tabelas do banco de dados, permitindo que o banco evolua sem quebrar os consumidores da API.
* **Negativas:**
    * Adiciona uma camada extra de abstração e um arquivo adicional por entidade, o que é um custo mínimo em troca dos benefícios de segurança e manutenibilidade.
