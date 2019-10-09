# webhook-builder

This php script builds a repository automatically after a commit is pushed to GitHub using webhooks. It should be placed in a web server, so GitHub can access it and create a POST request.

## Getting Started:
1. Clone the repository to be built and change the repository directory ownership to www-data: `sudo chown -R www-data:www-data /path/to/repository`.
2. Copy the php script to somewhere inside your web server.
3. Create a webhook on GitHub, setting the payload URL as the location used in the previous step.
5. Set the `repositoryPath` and `hookSecret` variables at the beginning of the code.
6. Change the build command according to your project. The default is simply `make`.
