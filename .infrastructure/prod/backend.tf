
terraform {
  backend "s3" {
    bucket = "terraform-paystride"
    key    = "infrastructure/prod/terraform.tfstate"
    region = "us-east-1"
  }
}


