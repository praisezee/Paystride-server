#create vpc
resource "aws_vpc" "main" {
  cidr_block = "10.0.0.0/16"
  tags = {
    Name = "Paystride-VPC"
 }
}


# create public subnet 
resource "aws_subnet" "public-subnet" {
 vpc_id     = aws_vpc.main.id
 cidr_block = "10.0.1.0/24"
 availability_zone = "us-east-1b"
 tags = {
  Name = "paystride_public_subnet"
 }
}

# create private subnet 
resource "aws_subnet" "public-subnet-2" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.2.0/24"
  availability_zone = "us-east-1c"

  tags = {
    Name = "paystride_prublic_subnet-2"
  }
}


#creating internet gateway 
resource "aws_internet_gateway" "altschool_ig" {
  vpc_id = aws_vpc.main.id

  tags = {
    Name = "Paystride Internet Gateway"
  }
}


# create route table
resource "aws_route_table" "public_rt" {
  vpc_id = aws_vpc.main.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.altschool_ig.id
  }

  route {
    ipv6_cidr_block = "::/0"
    gateway_id      = aws_internet_gateway.altschool_ig.id
  }

  tags = {
    Name = "Paystride Public Route Table"
  }
}

# route table association
resource "aws_route_table_association" "pub1" {
  subnet_id      = aws_subnet.public-subnet.id
  route_table_id = aws_route_table.public_rt.id
}

resource "aws_route_table_association" "pub2" {
  subnet_id      = aws_subnet.public-subnet-2.id
  route_table_id = aws_route_table.public_rt.id
}


