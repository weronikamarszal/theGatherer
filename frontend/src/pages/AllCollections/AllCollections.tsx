import {FunctionComponent, useEffect, useState} from "react";
import {CollectionsList} from "../../views/CollectionsList";

export const AllCollections: FunctionComponent = () => {

  const [collectionsList, setCollectionsList] = useState<any[]>([]);
  useEffect(() => {
    const apiUrl = `http://127.0.0.1:8000/api/get-collections`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setCollectionsList(res)
      })
  },[setCollectionsList]);

  return <div>
    <h1>THE GATHERER</h1>
    <h3>All collections:</h3>
    <CollectionsList onDelete={undefined} collectionsList={collectionsList}/>
  </div>;
};
