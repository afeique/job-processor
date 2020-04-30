# Glu Technical Assessment

## Requirements

Docker.

## Running application

	docker-compose up

Starts a PHP and MySQL container on localhost (127.0.0.1) at ports 80 and 3306 respectively. Some basic test data is loaded automatically.

The entire system can be refreshed using:

	docker-compose down

And then starting it back up again.

## API Endpoints

All data is returned as JSON. The endpoints are implemented in `src/index.php`.

- `GET /` Returns a listing of all jobs
- `GET /job` Returns the highest priority job, the next job to be processed
- `GET /job/$id` Returns the job with the specified ID
- `GET /process` Processes the highest priority job
- `GET /process/$id` Processes (or re-processes) the job with the specified ID
- `GET /users` Lists all users
- `GET /user/$id` Returns the specified user and all their jobs
- `POST /job` Adds a new job (params: `cmd, priority, user_id`)
- `POST /register` Registers a new user (params: `name`)


## Scalability

The attached System Diagram illustrates a basic scalable distributed system.

![System Diagram](https://github.com/afeique/job-processor/blob/master/SystemDiagram.png)

This design can be further scaled by:
- Separating the API servers into read/write servers
- Adding load balancers in front of the API servers
- Implementing some form of key:value memory caching for read accesses with a least recently used eviction policy