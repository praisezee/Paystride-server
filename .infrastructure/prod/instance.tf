resource "aws_instance" "paystride-backend"{
  ami           = "ami-00874d747dde814fa"
  instance_type = "t2.small"
  key_name       = "paystride"
  associate_public_ip_address = true
  subnet_id      = aws_subnet.public-subnet.id
  vpc_security_group_ids      = [aws_security_group.paysrtide_backend.id]
tags = {
    Name = "paystride-backend-prodd"
  }
}

resource "aws_instance" "paystridedb"{
  ami           = "ami-00874d747dde814fa"
  instance_type = "t2.micro"
  key_name       = "paystride"
  associate_public_ip_address = true
  subnet_id      = aws_subnet.public-subnet-2.id
  vpc_security_group_ids      = [aws_security_group.paysrtide_db.id]
tags = {
    Name = "paystridedb-prod"
  }
}
