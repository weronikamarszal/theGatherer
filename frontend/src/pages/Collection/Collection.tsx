import React, {FunctionComponent, useEffect, useState} from 'react';
import '../../index.css';
import {Link, useHistory, useParams} from "react-router-dom";
import {Button, Col, message, Popconfirm, Row, Space} from "antd";
import './Collection.css'
import {EditOutlined} from "@ant-design/icons";

function confirm(collectionId: number, history) {
  fetch (`/api/delete-collection/${collectionId}`, {
    method: 'POST',
  })
    .then(res => res.json())
    .then(() => {
      history.push(`/all-collections`)
      message.success('Collection deleted')
    })
}


export const Collection: FunctionComponent = () => {
  let params = useParams<any>();
  const collectionId = Number(params.id)
  const history = useHistory();

  const [collection, setCollection] = useState<any>({});
  useEffect(() => {
    fetch(`/api/get-collections/${collectionId}`)
      .then(res => res.json())
      .then(res => {
        setCollection(res)
      })
  }, [setCollection]);


  const [collectionItems, setCollectionsItems] = useState<any[]>([]);
  useEffect(() => {
    const apiUrl = `/api/get-collection-objects/${collectionId}`;
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
          <h1>{collection.name}</h1>
        </div>
        <div className='addButtonWrapper'>
          <Space>
            <Button shape="round"><Link to={`/add-object/${params.id}`}> Add Item </Link></Button>
            <Button shape="round"><Link to={`/edit-collection/${params.id}`}> Edit collection </Link>
            </Button>
            <Popconfirm
              title="Are you sure delete this object?"
              onConfirm={() => confirm(collectionId, history)}
              okText="Yes"
              cancelText="No">
              <Button shape="round">Delete</Button>
            </Popconfirm>
          </Space>
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
      <Row>
        {collectionItems.map((item) =>
          <Col span={8} className='collection-item-wrapper'>
            <div className='collection-item'>
              <img className='itemImage' src={item.photo_path} alt="itemImage"/>
              <div className='itemDescription'>
                {item.name}
              </div>
              <Button type="primary"><Link to={`/object/${item.id}`}> View </Link></Button>
            </div>
          </Col>
        )}
      </Row>

    </div>
  </>
};



