#!/usr/bin/env bash

# Checks whether sites on a docroot pair have gardens.profile enabled.

domain_suffix=$1
sitegroup=$2
environment=$3
interval=${4:-1}

if [[ $# -lt 3 ]]; then
  echo "Usage: $(basename $0) <domain_suffix> <sitegroup> <environment>"
  exit 1
fi

echo -e "\e[0;33mRunning on *${domain_suffix} sites in ${sitegroup}.${environment} at ${interval} second intervals.\e[0m"

json_file="/mnt/gfs/${sitegroup}.${environment}/files-private/sites.json"
echo -e "Grepping \e[0;34m${json_file}\e[0m..."
site_list=$(grep -o "[^\"]*${domain_suffix}" "$json_file")

output_file="/home/rburford/check-profile-${sitegroup}-${environment}.csv"
echo -e "Writing output to \e[0;32m${output_file}\e[0m."

for uri in $site_list; do
  echo -n "${domain_suffix}," | tee -a $output_file
  echo -n "${sitegroup}.${environment}," | tee -a $output_file
  echo -n "${uri}," | tee -a $output_file
  drush ev '$status = db_query("select status from {system} where name = :name", array(":name" => "gardens"))->fetchField(); echo "$status\n";' --root="/var/www/html/${sitegroup}.${environment}/docroot" --uri=$uri 2>/dev/null | tee -a $output_file
  sleep $interval
done

