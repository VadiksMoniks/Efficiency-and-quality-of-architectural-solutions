У виправленому коді функціонал відправлення запиту до API винесено у окремий клас, який наслідується класом GeoApi.php
Також, у класі GeoApi.php деякий функціонал, що повторюється винесено у окремі методи, як наприклад, отримання токена авторизації а також отримання данних про населений пункт (методи getAccessTocken та getCityInfo)
