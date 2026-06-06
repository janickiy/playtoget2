# Docker setup

Start the project:

```sh
docker compose -f docker/docker-compose.yml up -d --build
```

Open the site:

```text
http://127.0.0.1:8080/
```

MySQL from the host:

```text
host: 127.0.0.1
port: 3307
database: playtoget
user: playtoget
password: playtoget
```

The dump `janicky_playtoge.sql` and schema migrations from `database/` are mounted into MySQL init scripts and run only when the `site5-local_mysql_data` volume is created for the first time.

Recreate the database and import the dump again:

```sh
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up -d --build
```
