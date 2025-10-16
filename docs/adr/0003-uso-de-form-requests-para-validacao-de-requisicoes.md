# 3. Uso de Form Requests para Validação de Requisições

**Status:** Aceito

**Contexto:**
A validação de dados de entrada (`requests`) é uma parte crítica da segurança e integridade de qualquer API. Colocar a lógica de validação diretamente nos métodos dos controllers pode torná-los inchados ("Fat Controllers"), misturando responsabilidades (validação, autorização, lógica de negócio) e dificultando a leitura e a manutenção do código.

**Decisão:**
Toda a lógica de validação e autorização de requisições será encapsulada em [classes de `Form Request`](https://laravel.com/docs/11.x/validation#form-request-validation) dedicadas. O controller receberá a `Form Request` por injeção de dependência. Se a validação ou autorização falhar, o Laravel automaticamente retornará uma resposta de erro `422` ou `403`, respectivamente, antes que o código do controller seja executado.

**Consequências:**
* **Positivas:**
    * **Controllers Enxutos (Thin Controllers):** Remove completamente a lógica de validação dos controllers, tornando-os mais limpos e focados exclusivamente na lógica de negócio principal.
    * **Responsabilidade Única:** Isola a validação em sua própria classe, seguindo o Princípio da Responsabilidade Única (SRP).
    * **Autorização Centralizada:** O método `authorize()` dentro da `Form Request` permite centralizar a verificação de permissões do usuário junto com a validação dos dados.
    * **Reutilização:** As regras de validação podem ser facilmente reutilizadas em diferentes endpoints se necessário.
* **Negativas:**
    * Para validações extremamente simples, pode parecer um formalismo excessivo, mas estabelece um padrão de alta qualidade que escala bem conforme a complexidade da aplicação aumenta.