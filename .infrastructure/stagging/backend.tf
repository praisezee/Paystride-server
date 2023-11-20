
terraform {
  backend "s3" {
    bucket = "terraform-paystride"
    key    = "infrastructure/stagging/terraform.tfstate"
    region = "us-east-1"
  }
}


