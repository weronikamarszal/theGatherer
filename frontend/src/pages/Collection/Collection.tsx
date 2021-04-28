import React, {FunctionComponent, useEffect, useState} from 'react';
import '../../index.css';
import itemImage from '../../itemsImages/stampImage.png';
import {CollectionsList} from "../../views/CollectionsList";
import {Link, useParams} from "react-router-dom";

export const Collection: FunctionComponent = () => {
  let params = useParams<any>();


  const [collectionItems, setCollectionsItems] = useState<any[]>([]);
  useEffect(() => {
    const apiUrl = `/api/get-collection-objects/${params.id}`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setCollectionsItems(res)
      })
  }, [setCollectionsItems]);

  return <>
    <div>
      <div className='headWrapper'>
        <div className='thisCollectionTitle'>
          <h1>This collection</h1>
        </div>
        <div className='addButtonWrapper'>
          <button className='addCollectionButton'> <Link to={`/${params.id}/add-object`}> Add Item </Link> </button>
        </div>
      </div>
      <hr className='horizontalLine'/>
      <div className="dropdown">
        <button className="dropbtn">Sort by...</button>
        <div className="dropdown-content">
          <button className='sortButton'>Dimensions</button>
          <button className='sortButton'>Material</button>
          <button className='sortButton'>Date</button>
          <button className='sortButton'>Standard</button>
        </div>
      </div>
      <div className='itemsList'>
        {collectionItems.map((item) =>
          <div className='item'>
            <img className='itemImage' src={item.photo_path} alt="itemImage"/>
            <div className='itemDescription'>
              {item.name}
            </div>
            <button className='viewItem'> <Link to={`/object/${item.id}`}> View </Link></button>
          </div>
        )}
      </div>
    </div>
  </>
};

