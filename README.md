# nvd-scrapper
Pull data from the national vulnerability database and push it to a GCP bucket

`sudo docker run -e BUCKET={gcp_bucket_name} -e PROJECTID={gcp_project_id} -e GOOGLE_APPLICATION_CREDENTIALS={gcp_service_acc_creds} -v $(pwd):/google/ --rm nvd-scrapper`