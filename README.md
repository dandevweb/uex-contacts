# UEX Contacts

## Executando o projeto

O projeto pode ser executado normalmente em qualquer sistema operacional com os seguintes pré-requisitos:

-   PHP 8.3
-   Composer
-   MySql
-   Servidor Apache ou Nginx
-   NPM 9.\*

**Execute os seguintes comandos:**

Clone o projeto

```bash
git clone https://github.com/dandevweb/uex-contacts.git

```

Entre no diretório do projeto

```bash
  cd uex-contacts

```

Crie o arquivo .env a partir do arquivo .env.example

```bash
  cp .env.example .env
```

**Insira os dados do banco manualmente no arquivo .env ou utlize o próprio sqlite já pre-setado**

Instale as dependências

```bash
    composer install
```

```bash
    npm install
```

Gere a chave

```bash
  php artisan key:generate
```

Executando os testes

```bash
  php artisan test
```

Caso queria utilizar um banco local "zerado", execute as migrations e seeders

```bash
  php artisan migrate --seed
```

Execute o servido embutido do Laravel

```bash
  php artisan serve
```

### Configuração de Funcionalidades

-   **Reset de Senha**: Para habilitar a funcionalidade de reset de senha, configure o envio de emails no arquivo `.env`. Alternativamente, você pode utilizar o log predefinido que já vem configurado no projeto.

-   **Cadastro de Contatos**: Para implementar o cadastro de contatos, será necessário criar uma chave de API do Google Maps. Certifique-se de configurar o arquivo `.env` com a variável `GOOGLE_MAPS_API_KEY`.

-   **Husky**: Utilizamos a ferramenta Husky como uma dependência de desenvolvimento para automatizar a execução de testes, realizar análise estática e formatar o código em cada commit realizado. Isso garante que o código mantenha um padrão de qualidade e esteja sempre funcional.

-   **Documentação da API**: Para mais informações sobre como utilizar a API, siga a [Documentação da API](https://documenter.getpostman.com/view/22300616/2sAY4sjjLm). Lembre-se de alterar a URL base para `http://localhost:8080` para testes locais.
