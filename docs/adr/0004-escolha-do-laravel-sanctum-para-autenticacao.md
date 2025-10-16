# 4. Escolha do Laravel Sanctum para Autenticação

**Status:** Aceito

**Contexto:**
A API precisa de um mecanismo de autenticação seguro para proteger seus endpoints. As requisições serão feitas por consumidores "first-party", como um Single Page Application (SPA) ou um aplicativo móvel desenvolvido pela própria equipe. O mecanismo precisa ser leve, seguro e bem integrado ao ecossistema Laravel.

**Decisão:**
A autenticação da API será implementada utilizando [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum). Os usuários se autenticarão através de um endpoint de login que retornará um token de API opaco. Este token será então enviado no cabeçalho `Authorization` de requisições subsequentes para acessar rotas protegidas.

**Consequências:**
* **Positivas:**
    * **Leveza e Foco:** Sanctum é uma solução desenhada especificamente para este cenário, evitando a complexidade de um servidor OAuth2 completo (como o Laravel Passport), que seria um exagero para o escopo do projeto.
    * **Segurança:** Os tokens são armazenados com hash no banco de dados, permitindo que sejam facilmente revogados a qualquer momento (por exemplo, em uma funcionalidade de "logout").
    * **Integração Nativa:** Sendo um pacote oficial, sua integração com os sistemas de autenticação e middleware do Laravel é perfeita.
    * **Ideal para o Escopo:** Alinha-se perfeitamente com os requisitos do desafio, que envolvem uma API consumida por clientes diretos.
* **Negativas:**
    * Não é uma solução adequada se a API precisasse suportar autenticação de aplicações de terceiros (cenário OAuth2), o que não é um requisito deste projeto.
