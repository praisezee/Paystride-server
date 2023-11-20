resource "aws_instance" "paystride-backend"{
  ami           = "ami-00874d747dde814fa"
  instance_type = "t2.small"
  key_name       = "paystride"
  associate_public_ip_address = true
  vpc_security_group_ids      = [aws_security_group.paysrtide_sg-stagging.id]
tags = {
    Name = "paystride-backend-prod-staggingg"
  }
}

