<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    3.0.0
 */
defined('_JEXEC') or die;
use QuixNxt\Utils\Schema;

$qx_elements_schema = Schema::getAvailableElements();
$root_uri = JUri::root(true);
?>

<script type="text/javascript" defer>
  const dbName = 'Quix@v4.x.x Cache Storage';
  const storeName = 'keyvaluepairs';
  const QX_ELEMENTS_SCHEMA = <?php echo $qx_elements_schema ?? '[]' ?>;

  setTimeout(boot, 0);
  async function boot() {
    const exists = await doesDatabaseExistAndHasMinimumEntries(dbName, storeName, QX_ELEMENTS_SCHEMA?.length ?? 100);
    const shouldClearCache = localStorage.getItem('clean-qx-admin-cash');
    const spinner = document.getElementById('admin-cache-clean-loader');
    const loader = spinner.querySelector(".loader");
    const messages = ['Setting up', 'Your data', 'Just a moment!'];
    let messageIndex = 0;
    let intervalId = 0;

    if(shouldClearCache === 'true') {
      if(exists){
        spinner.classList.remove('qx-hidden');
        intervalId = setInterval(updateLoaderText, 1000);
        await deleteDatabase();
      }
    }

    if (!shouldClearCache && exists) {
      intervalId && spinner.classList.add('qx-hidden');
      intervalId && clearInterval(intervalId);
      console.log(`Database "${dbName}" already exists. Skipping creation.`);
      return;
    }

    if(!QX_ELEMENTS_SCHEMA) {
      intervalId && spinner.classList.add('qx-hidden');
      intervalId && clearInterval(intervalId);
      return console.error('No elements schema found');
    }

    !intervalId && spinner.classList.remove('qx-hidden');
    !intervalId && (intervalId = setInterval(updateLoaderText, 1000));

    function updateLoaderText() {
      loader.style.setProperty('--loading-text', `'${messages[messageIndex]}...'`);
      messageIndex = (messageIndex + 1) % messages.length;
    }

    try{
      await clearCache();

      const data = await loadElements(QX_ELEMENTS_SCHEMA);
      for (const key in data.elements) {
        if (!data.elements.hasOwnProperty(key)) {
          continue;
        }
        data.elements[key].default_node = JSON.parse(
          data.elements[key].default_node
        );
        data.elements[key].schema = JSON.parse(
          data.elements[key].schema
        );
      }

      await Promise.all([
        cacheElements(data.elements),
        cacheElements(data.special),
        cacheElements({ js: data.js, css: data.css }),
      ]);
      console.log('Elements cached successfully');
    } catch (error) {
      await deleteDatabase();
      console.error('ERROR CACHING ELEMENTS:', error);
    } finally{
      clearInterval(intervalId);
      spinner.classList.add('qx-hidden');
      if(shouldClearCache) localStorage.removeItem('clean-qx-admin-cash');
    }

  }

  async function clearCache() {
    const link = (perm) => `index.php?option=com_quix&task=cache.${perm}&format=json`;
    try {
      // await fetch(link("cleanImages"));
      await fetch(link("cleanPages"));
      await fetch("index.php?option=com_quix&task=clear_cache&step=0");

    } catch(err){
      console.error(err);
    }
  }

  function openDatabase() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(dbName, 3);

      request.onerror = (event) => {
        reject(event.target.errorCode);
      };

      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        db.createObjectStore(storeName);
      };

      request.onsuccess = (event) => {
        resolve(event.target.result);
      };
    });
  }

  async function deleteDatabase() {
    const deleteRequest = indexedDB.deleteDatabase(dbName);

    return new Promise((resolve, reject) => {
      deleteRequest.onerror = (event) => {
        reject(event.target.errorCode);
      };

      deleteRequest.onsuccess = () => {
        resolve();
      };
    });
  }

  async function doesDatabaseExistAndHasMinimumEntries(dbName, storeName, minEntries) {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(dbName);

      request.onupgradeneeded = () => {
        // Database does not exist and is being created
        request.transaction.abort(); // Prevent any changes
        resolve(false);
      };

      request.onsuccess = () => {
        // Database exists
        const db = request.result;
        const tx = db.transaction(storeName, 'readonly');
        const store = tx.objectStore(storeName);
        const countRequest = store.count();

        countRequest.onsuccess = () => {
          db.close(); // Close the database connection
          resolve(countRequest.result >= minEntries);
        };

        countRequest.onerror = () => {
          db.close(); // Ensure the database is closed even on error
          reject(countRequest.error);
        };
      };

      request.onerror = (event) => {
        // An error occurred while opening the database
        reject(event.target.errorCode);
      };
    });
  }


  async function storageSetAsync(key, value) {
    const db = await openDatabase();
    const tx = db.transaction(storeName, 'readwrite');
    const store = tx.objectStore(storeName);
    const request = store.put(value, key);

    return new Promise((resolve, reject) => {
      request.onsuccess = () => resolve();
      request.onerror = () => reject(request.error);
    });
  }


  async function retrieveData(key) {
    const db = await openDatabase();
    const tx = db.transaction(storeName, 'readonly');
    const store = tx.objectStore(storeName);
    const request = store.get(key);
    return new Promise((resolve, reject) => {
      request.onsuccess = (event) => resolve(event.target.result.value);
      request.onerror = () => reject();
    });
  }

  async function loadElements(elements = []) {
    const parts = splitIntoParts(elements, 3);

    // Function to create a promise for an API call with given elements
    const fetchElements = async (part) => {
      const elements = part.map((element) => element.slug);
      const url = `<?= $root_uri ?>/index.php?option=com_quix&task=element.getElementsData&elements=${elements.join(',')}`;
      const res = await fetch(url);
      if (!res.ok) throw new Error('Failed to load elements');
      const resClone = res.clone();

      try {
        return await res.json();
      } catch (err) {
        console.log('JSON parsing failed, attempting to clean response');
        const text = await resClone.text();
        // Find the position where JSON starts (looking for first '{')
        const jsonStartIndex = text.indexOf('{');
        if (jsonStartIndex === -1) {
          throw new Error('No JSON found in response');
        }
        // Extract only the JSON part
        const cleanJson = text.substring(jsonStartIndex);
        try {
          return JSON.parse(cleanJson);
        } catch (secondErr) {
          console.log('Failed to parse cleaned response:', secondErr);
          console.log('Original response:', text);
          throw secondErr;
        }
      }
    };

    try {
        // Map each part to a promise and wait for all to complete
        const responses =  [];
        for(const part of parts){
          try{
            const {data} = await fetchElements(part);
            responses.push(data);
          } catch (error) {
            console.error('Error loading elements:', error);
            return false;
          }
        }
        // Merge the data from each response
        return new Promise((resolve) =>{
          const combinedData = responses.reduce((acc, data) => {
            for (const key in data) {
              //if key is js or css, merge the data as array not object
              if (key === 'js' || key === 'css') {
                acc[key] = [...(acc[key] || []), ...data[key]];
              } else if (data.hasOwnProperty(key)) {
                acc[key] = {...acc[key], ...data[key]};
              }
            }
            return acc;
          }, {});

          resolve(combinedData);
        }) ;

    } catch (error) {
        console.error('Error loading elements:', error);
        throw error;
    }
}

  function splitIntoParts(array, parts) {
      let result = [];
      for (let i = 0; i < parts; i++) {
          const start = Math.ceil((i * array.length) / parts);
          const end = Math.ceil(((i + 1) * array.length) / parts);
          result.push(array.slice(start, end));
      }
      return result;
  }

  async function cacheElements(elements) {
    const db = await openDatabase();
    return new Promise((resolve, reject) => {
        const promises = [];
        for (const element in elements) {
            if (!elements.hasOwnProperty(element)) {
                continue;
            }

            if (typeof elements[element] === 'string' || Array.isArray(elements[element])) {
                storageSetAsync(element, elements[element], db);
                continue;
            }

            for (const key in elements[element]) {
                if (!elements[element].hasOwnProperty(key)) {
                    continue;
                }

                if (elements[element][key]) {
                    storageSetAsync(`${element}-${key}`, elements[element][key], db);
                }
            }
        }

        Promise
            .all(promises)
            .then(() => {
                resolve();
            })
            .catch(reject);
    });
  }
</script>
