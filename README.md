# nvd-scrapper
Pull data from the national vulnerability database and push it to a GCP bucket

Bucket is optional, you can leave out ENV vars regarding bucket, and just pull the data from container stdout

`sudo docker run -e BUCKET={gcp_bucket_name} -e PROJECTID={gcp_project_id} -e GOOGLE_APPLICATION_CREDENTIALS={gcp_service_acc_creds} -v $(pwd):/google/ --rm nvd-scrapper`

https://earthroot.github.io/nvd-scrapper/