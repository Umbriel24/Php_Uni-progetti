# Usa l'immagine base XAMPP
FROM tomsik68/xampp:7.4.33

# Copia i file dei progetti nel container
COPY ./progetto1 /www/progetto1
COPY ./progetto2 /www/progetto2

# Imposta i permessi corretti per i file
RUN chmod -R 755 /www/progetto1 /www/progetto2

# Aggiungi i progetti alla configurazione di Apache (httpd.conf)
RUN echo 'Include /www/progetto1/.htaccess' >> /opt/lampp/etc/httpd.conf
RUN echo 'Include /www/progetto2/.htaccess' >> /opt/lampp/etc/httpd.conf

# Abilita mod_rewrite per Apache (necessario per molte applicazioni PHP)
RUN echo 'LoadModule rewrite_module modules/mod_rewrite.so' >> /opt/lampp/etc/httpd.conf

# Assicurati che Apache carichi i progetti correttamente
RUN echo 'DocumentRoot "/www"' >> /opt/lampp/etc/httpd.conf
RUN echo 'DirectoryIndex index.php index.html' >> /opt/lampp/etc/httpd.conf

# Esponi la porta 80 per il server web
EXPOSE 80

# Avvia Apache all'interno del container
CMD ["/opt/lampp/lampp", "start"]