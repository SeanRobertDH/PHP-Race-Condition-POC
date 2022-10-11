# PHP-Race-Condition-POC
This repo contains a PHP Race Condition POC that simulates a vulnerability seen in Starbucks gift cards in 2015.

# Prerequisites:
1. install [Docker](https://docs.docker.com/get-docker/).
2. Ensure that you're running the docker container in a multi-cpu or multi-threaded enviroment (Docker uses all available resources by default).

# Setup:
**Note*** May not work on M1 Mac or other ARM-based OSes. If you find a solution, please feel free to submit a PR!

1. Run `git clone https://github.com/SeanRobertDH/PHP-Race-Condition-POC.git`.
2. in the cloned directory with the `docker-compose.yml` file, run the command `docker-compose up`.
3. The vulnerable web server is now running on `localhost:6969`.
4. There should be '2 gift cards with $500 each'. Your goal is to increase the sum of their values to be more than $1000.

# Recommended Tools:
- [Race The Web](https://github.com/TheHackerDev/race-the-web)
- [Burpsuite](https://portswigger.net/burp/releases#community) (Turbo Intruder Extension)

# Resetting the container:
1. Open a seperate terminal also in the directory with the `docker-compose.yml` file and run the command `docker-compose down -v`.
