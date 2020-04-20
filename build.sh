dir=`dirname $0`

. ./config.sh

# remove ./build if exists
if [ -d $dir/build ]; then    
    rm -rf $dir/build
fi

mkdir build

# php files
cp $dir/*.php $dir/build

# includes
cp -r $dir/includes $dir/build

# style.css
cp $dir/style.css $dir/build

# styles
cp -r $dir/styles $dir/build

# js
cp -r $dir/js $dir/build

# assets
cp -r $dir/assets $dir/build

rm -rf $dir/build/assets/images

zipname=$(echo $THEME_NAME | tr '[:upper:]' '[:lower:]' | tr ' ' '_')_${THEME_VERSION};

cd build; zip -r ./$zipname.zip *

echo -e "\x1b[40mBuild done successfully.\x1b[0m"