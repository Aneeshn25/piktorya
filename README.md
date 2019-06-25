# piktorya
social-networking app

<b>microservices application</b>

step 1. get a ubuntu ec2 instance. Open security groups port 80

step 2. ssh to the instance

step 3. sudo apt update

step 4. install <a href="https://docs.docker.com/install/linux/docker-ce/ubuntu/" target="_blank">docker</a>

step 5. install <a href="https://docs.docker.com/compose/install/" target="_blank">docker compose</a>

step 6. run <code>git clone https://github.com/Aneeshn25/piktorya</code>
        
step 7. change the directory to piktorya <code>cd piktorya</code>

step 8. run <code>sudo docker-compose built</code>

step 9. run <code>sudo docker-compose up -d</code> run it in the background

step 10. Access piktorya app using <code>http://public_ip</code> of the instance using the http client(any web browser)

should work. if not try to make it work.
