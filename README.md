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

**Insira os dados do banco manualmente no arquivo .env**

```bash
  php artisan key:generate
```

Instale as dependências

```bash
    composer install
```

```bash
    npm install
```

Caso queria utilizar um banco local "zerado", execute as migrations e seeders

```bash
  php artisan migrate --seed
```

Execute o servido embutido do Laravel

```bash
  php artisan serve
```

Siga a [Documentação da API](https://documenter.getpostman.com/view/22300616/2sAY4sjjLm) alterando somente a url base para http://localhost:8080
