echo "Entrez le nom du fichier contenant les tests à effectuer, laissez vide si l'ensemble des tests doivent être effectués"
read input
symfony console d:d:d --force --env=test
symfony console d:d:c --env=test
symfony console d:m:m --no-interaction --env=test
if [ input ];
then
  php bin/phpunit tests/"$input"
else
  php bin/phpunit tests/
fi
read -p "Appuyez sur Entrée pour quitter..."