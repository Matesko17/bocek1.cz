# Database Setup

Place your MySQL dump file in this directory with the name `bocek_database.sql`.

The database will be automatically imported when the Docker containers start.

## Instructions:

1. Export your production database:
   ```bash
   mysqldump -u username -p database_name > bocek_database.sql
   ```

2. Place the `bocek_database.sql` file in this directory

3. The database will be automatically imported during container startup

Note: Make sure to update any domain references in the database dump to point to `http://localhost:8080` for local development.