FROM python:3.12-slim

WORKDIR /app
COPY requirements.txt /app/requirements.txt
RUN pip install --no-cache-dir -r /app/requirements.txt
COPY app.py /app/app.py

ENV PORT=8787
EXPOSE 8787

CMD ["gunicorn", "--workers", "2", "--bind", "0.0.0.0:8787", "app:app"]
