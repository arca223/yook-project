FROM jakzal/phpqa:latest

# Chromium and ChromeDriver
ENV PANTHER_NO_SANDBOX 1

# Not mandatory, but recommended
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage --window-size=1920,1080 --no-sandbox --headless --hide-scrollbars'

RUN apt-get update && apt-get install -y \
  chromium \
  chromium-driver \
  wget \
  && rm -rf /var/lib/apt/lists/*

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony