#!/bin/sh  

# config information �������ݿ�����  
db_host=localhost
db_port=3306
db_username=root
db_password="root"
db_name=youyiqianbao

backup_dir="/www/lanmps/db"

if [ ! -d "$backup_dir" ];then
mkdir "$backup_dir"
fi

today=`date "+%Y%m%d%H"`

# ������Ҫ���ݵ����ݿ������  

echo "================  begining backup basic data  ================="  

cd $backup_dir

# ����ĵľ�����仰��ʹ��mysqldump����ִ�б���  
mysqldump -h${db_host} -u $db_username -p${db_password}  $db_name  > $backup_dir/$today".sql"

finish_date=`date '+%Y-%m-%d %H:%M:%S'`

echo "The  backup successfully completed at ${finish_date}."  

one_days_ago=`date -d "1 days ago" +%Y%m%d`
two_days_ago=`date -d "2 days ago" +%Y%m%d`
three_days_ago=`date -d "3 days ago" +%Y%m%d`

# ����ɾ��  

#find $backup_dir -name "*${three_days_ago}*.sql" |grep -v "${one_days_ago}00.sql" |grep -v "${one_days_ago}06.sql" |grep -v "${one_days_ago}12.sql" |grep -v "${one_days_ago}18.sql" | xargs -i rm -f {}  

#find $backup_dir -name "*${two_days_ago}*.sql" |grep -v "${two_days_ago}00.sql" |grep -v "${two_days_ago}12.sql" | xargs -i rm -f {}  
#ɾ��3��֮ǰ��
find $backup_dir -name "*${three_days_ago}*.sql"|grep -v "${three_days_ago}.sql" | xargs -i rm -f {}
