API básica de produtos, utilizando Slim Framework para roteamento e SQLite para persistência.

### Testar

1. Clonar repositório
```sh
$ git clone https://github.com/NathanReis/ApiBasicaProdutos.git
```

2. Instalar dependências
```sh
$ cd ApiBasicaProdutos
$ php composer.phar install
```

3. Criar banco de dados
- Na pasta "source/database" criar um arquivo "db.sqlite"
- Utilizar o script do arquivo "script.sql" que está na mesma pasta

4. Iniciar servidor
```sh
$ cd public
$ php -S localhost:3333
```

5. Por fim, importar o arquivo "InsomniaTesteApi.json" no Insomnia ou qualquer software semelhante e começar os testes

### Campos

> ID (id)
    - Inteiro
    - Obrigatório
    - Gerado automáticamente ao cadastrar

>  Nome (name)
    - Texto
    - Obrigatório
    - Mínimo de 1 caractere
    - Máximo de 30 caracteres
    - Único

> Preço (price)
    - Monetário
    - Obrigatótio
    - Mínimo de R$ 0,00
    - Máximo de R$ 9.999,99

> Quantidade (amount)
    - Inteiro
    - Obrigatório
    - Mínimo de 0
    - Máximo de 255

### Cadastrar

**Método**
POST

**Rota**
localhost:3333/products

**Corpo de envio**
```json
{
    "amount": ,
    "name": "",
    "price": 
}
```

### Listar

**Método**
GET

**Rota**
localhost:3333/products

### Consultar

**Método**
GET

**Rota**
localhost:3333/products/{id}

### Atualizar

**Método**
PUT

**Rota**
localhost:3333/products/{id}

**Corpo de envio**
```json
{
    "amount": ,
    "name": "",
    "price": 
}
```

### Deletar

**Método**
DELETE

**Rota**
localhost:3333/products/{id}

### Comprar

**Método**
PUT

**Rota**
localhost:3333/buy-products

**Corpo de envio**
```json
[
    {
        "id": ,
        "amount": 
    },
    ...
]
```

### Vender

**Método**
PUT

**Rota**
localhost:3333/sell-products

**Corpo de envio**
```json
[
    {
        "id": ,
        "amount": 
    },
    ...
]
```
