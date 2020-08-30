# nvd-scrapper
Pull data from the national vulnerability database and push it to a GCP bucket

environment
- FORMAT: "json" or "csv"
- BUCKET: name of gcp bucket ( if empty bucket upload is ignored, and vars below)
- PROJECTID: get from gcp
- FILENAME: name of the file uploaded to bucket
- GOOGLE_APPLICATION_CREDENTIALS: path inside container where auth key is mounted

```dockerfile
docker run -e FILENAME="nvd-results.json" \
-e FORMAT="json" \
-e BUCKET={bucket-name} \
-e PROJECTID={project-id} \
-e GOOGLE_APPLICATION_CREDENTIALS=/google/key.json \
-v $(pwd):/google/ \
--rm clglavan/nvd-scrapper
```

### Example implementation
https://earthroot.github.io/nvd-scrapper/