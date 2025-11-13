<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel"></p>

# Sistema de Saldo com Laravel

Algumas pastas estão ignoradas pelo `.gitignore`.

Certifique-se de ter `php` e `composer` configurados na sua variável global `PATH` para realizar uma nova instalação do Laravel.

## Clonando o projeto

Considere que você esteja em um sistema operacional Linux ou Windows com o Git instalado e execute os passos a seguir:

1. **Clone o projeto**

   ```bash
   git clone https://github.com/Dhayllin/laravel_saldo.git
   ```

2. **Instale as dependências e o framework**

   ```bash
   composer install --no-scripts --ignore-platform-reqs
   ```

   > **Notas:**
   >
   > - O projeto utiliza Laravel 5.7, que requer PHP 7.1. O parâmetro
   >   `--ignore-platform-reqs` evita falhas quando a instalação é realizada em
   >   ambientes com PHP mais recente (por exemplo, 8.x).
   > - A aplicação já possui uma correção para lidar com o formato de metadados
   >   gerado pelo Composer 2. Caso encontre o erro `Undefined index: name` ao
   >   executar comandos Artisan, certifique-se de ter instalado as dependências
   >   recentemente para que o cache seja reconstruído com os novos metadados.

3. **Copie o arquivo `.env.example`**

   ```bash
   cp .env.example .env
   ```

4. **Crie uma nova chave para a aplicação**

   ```bash
   php artisan key:generate
   ```

5. **Atualize o arquivo `.env`** com as configurações abaixo:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=homestead
   DB_USERNAME=homestead
   DB_PASSWORD=secret
   ```

6. **Execute as migrations e os seeders**

   ```bash
   php artisan migrate --seed
   ```

## Executando com Docker

O projeto fornece um ambiente Docker que automatiza toda a preparação descrita acima. Ao executar o comando abaixo, o container da aplicação irá:

- instalar as dependências com `composer install --no-scripts --ignore-platform-reqs`;
- garantir que o arquivo `.env` exista e esteja configurado com as variáveis de banco de dados listadas anteriormente;
- gerar a chave da aplicação;
- executar `php artisan migrate --seed` automaticamente.

Para iniciar o ambiente, utilize:

```bash
docker-compose up --build
```

Após o container subir, a aplicação estará disponível em `http://localhost:8000`.
