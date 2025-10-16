# 5. Documentação com OpenAPI (Swagger)

**Status:** Aceito

**Contexto:**
Uma API é um produto para outros desenvolvedores (sejam eles do time de frontend ou externos). Sem uma documentação clara, interativa e atualizada, o processo de integração se torna lento, propenso a erros e frustrante. Manter a documentação manualmente em um arquivo separado é um processo que frequentemente leva a inconsistências com o código real.

**Decisão:**
A documentação da API será gerada automaticamente a partir de anotações (PHPDoc) diretamente nos arquivos de controller, seguindo o padrão OpenAPI. Será utilizado um pacote como o `darkaonline/l5-swagger` para processar essas anotações e gerar uma interface de usuário Swagger interativa.

**Consequências:**
* **Positivas:**
    * **Fonte Única da Verdade:** A documentação vive junto com o código que ela descreve. Isso reduz drasticamente a chance de a documentação ficar desatualizada.
    * **Experiência do Desenvolvedor (DX):** Fornece uma interface interativa onde os consumidores da API podem ler sobre os endpoints, entender os parâmetros e até mesmo testar as requisições diretamente do navegador.
    * **Agilidade na Integração:** Acelera o trabalho da equipe de frontend, que pode começar a consumir a API sem precisar de assistência constante do time de backend.
    * **Profissionalismo:** A entrega de uma API auto-documentada é um sinal de maturidade e qualidade no desenvolvimento de software.
* **Negativas:**
    * Adiciona uma pequena sobrecarga de trabalho ao escrever as anotações nos controllers. No entanto, esse custo é pago rapidamente pela facilidade de manutenção e clareza obtida.