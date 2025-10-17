# 10. Implementação de Autorização Baseada em Proprietário (Ownership) nos Form Requests

**Status:** Aceito

**Contexto:**
Foi identificada uma falha de segurança crítica na funcionalidade de atualização de perfil. O método `authorize()` na classe `UpdateUserRequest` retornava `true` por padrão. Isso permitia que qualquer usuário autenticado pudesse modificar o perfil de qualquer outro usuário, simplesmente alterando o ID na URL da requisição (`PATCH /api/users/{id}`). Esta vulnerabilidade é conhecida como Referência Insegura a Objetos Diretos (IDOR - Insecure Direct Object Reference) e viola o Princípio do Menor Privilégio.

**Decisão:**
Para corrigir a vulnerabilidade e garantir que os usuários só possam modificar seus próprios dados, a lógica de autorização foi implementada diretamente no método `authorize()` da classe `UpdateUserRequest`. A regra compara o ID do usuário autenticado (`$this->user()->id`) com o ID do usuário que está sendo alvo da requisição (`$this->route('user')->id`). A ação só é permitida se os IDs forem idênticos. Além disso, foram criados testes de feature específicos para cobrir os seguintes cenários:
1.  Um usuário pode atualizar seu próprio perfil (esperado `200 OK`).
2.  Um usuário **não pode** atualizar o perfil de outro usuário (esperado `403 Forbidden`).
3.  Um usuário não autenticado não pode atualizar nenhum perfil (esperado `401 Unauthorized`).

**Consequências:**
* **Positivas:**
    * **Segurança Reforçada:** A vulnerabilidade IDOR foi completamente eliminada. A aplicação agora garante a propriedade dos dados, um pilar fundamental da segurança.
    * **Centralização da Lógica de Autorização:** A responsabilidade de verificar a permissão está encapsulada junto com a validação dos dados, mantendo os controllers limpos e alinhados com o Princípio da Responsabilidade Única.
    * **Respostas HTTP Automáticas e Corretas:** O Laravel gerencia automaticamente o envio de uma resposta `403 Forbidden` quando a autorização falha, simplificando o fluxo do controller.
    * **Prevenção de Regressões:** A existência de testes automatizados para a lógica de autorização garante que esta falha de segurança não seja reintroduzida acidentalmente no futuro.
    * **Código Limpo e Explícito:** A regra de autorização é clara, legível e localizada no lugar mais apropriado dentro da arquitetura Laravel.

* **Negativas:**
    * Nenhuma consequência negativa foi identificada. Esta implementação é uma correção essencial e segue as melhores práticas de desenvolvimento seguro.