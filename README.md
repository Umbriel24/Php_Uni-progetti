# Running the Project with Docker

This project is configured to run using Docker and Docker Compose. Follow the steps below to set up and run the project:

## Requirements

- Docker version 20.10 or higher
- Docker Compose version 1.29 or higher

## Environment Variables

The following environment variables are required and can be configured in the `docker-compose.yml` file:

- `MYSQL_ROOT_PASSWORD`: Password for the MySQL root user (default: `example`)
- `MYSQL_DATABASE`: Name of the MySQL database (default: `app_db`)

## Build and Run Instructions

1. Clone the repository and navigate to the project directory.
2. Build and start the services using Docker Compose:

   ```bash
   docker-compose up --build
   ```

3. Access the services via the following ports:

   - `progetto1`: [http://localhost:8081](http://localhost:8081)
   - `progetto2`: [http://localhost:8082](http://localhost:8082)

## Special Configuration

- The `db` service uses a named volume `db_data` to persist MySQL data.
- Ensure the `app_network` network is properly configured for inter-service communication.

## Exposed Ports

- `progetto1`: 8081
- `progetto2`: 8082
- `db`: Not exposed externally

For further details, refer to the `Dockerfile` and `docker-compose.yml` files included in the project.