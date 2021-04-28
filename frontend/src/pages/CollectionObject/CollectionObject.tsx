import {FunctionComponent, useEffect, useState} from "react";
import {Button, Col, Divider, Row, Table} from "antd";
import { useParams, useRouteMatch, } from 'react-router-dom';

export const CollectionObject: FunctionComponent = () => {

  let collectionId = useParams<any>();

  const [collectionObject, setObject] = useState<any>({});
  useEffect(() => {
    const apiUrl = `/api/get-object/${collectionId.id}`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setObject(res)
      })
  },[setObject]);

  const columns = [
    {
      title: 'attribute',
      dataIndex: 'attribute',
    },
    {
      title: 'value',
      dataIndex: 'value',
    },
  ];

  const allAttributes = Object.keys(collectionObject)
  const size = allAttributes.length;
  const partialAttributes = allAttributes.slice(4-size)
  const data = [];

  allAttributes.forEach(attribute => data.push({
    attribute: attribute,
    value: collectionObject[attribute]
  }))


  const photoPath = collectionObject.photo_path ? <img src={collectionObject.photo_path} /> : '';

  return <div>
    <Row>
      <Col span={12}>
        <h1>
          {collectionObject.name}
        </h1>
      </Col>
      <Col span={12}>
        <Button type="primary" shape="round">Delete</Button>
      </Col>
    </Row>
    <Divider />
    {photoPath}

    <p></p>
    <Table columns={columns} dataSource={data} />
  </div>;

};
