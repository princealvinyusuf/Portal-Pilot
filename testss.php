curl -X POST "http://api.close.dev.bri.co.id:5557/gateway/apiActiveDirectory/1.0/ADAuthentication2" \
-H "Content-Type: application/json" \
-d '{
  "userLogin": "your_username",
  "password": "your_password",
  "channelId": "Test Channel",
  "userAgent": "Firefox",
  "ipAddress": "your_ip_address"
}'
