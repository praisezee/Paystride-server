resource "aws_instance" "web"{
  ami           = "ami-00874d747dde814fa"
  instance_type = "t2.micro"
  key_name       = "paystride"
  associate_public_ip_address = true
  subnet_id      = aws_subnet.public-1-subnet.id
  vpc_security_group_ids      = [aws_security_group.web_sg.id]
tags = {
    Name = "web"
  }
}
