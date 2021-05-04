import {FunctionComponent, useEffect, useState} from "react";
import {Button, Col, Divider, Row, Table} from "antd";
import {useParams, useRouteMatch,} from 'react-router-dom';
import {ColumnsType} from "antd/es/table";
import './CollectionObject.css'


export const CollectionObject: FunctionComponent = () => {

  let collectionId = useParams<any>();

  const [collectionObject, setObject] = useState<any>({});
  const [collectionAttributes, setAttributes] = useState<any>([]);
  useEffect(() => {
    const apiUrl = `/api/get-object/${collectionId.id}`;
    fetch(apiUrl)
      .then(res => res.json())
      .then(res => {
        setObject(res)
        return fetch(`/api/get-collection-attributes/${res.collection_id}`)
      })
      .then(res => res.json())
      .then(res => setAttributes(res))
  }, [setObject, setAttributes]);

  const columns: ColumnsType = [
    {
      title: 'attribute',
      dataIndex: 'attribute',
      render: value => <div>{value.label}</div>
    },
    {
      title: 'value',
      dataIndex: 'value',
      render: value => <div>{value}</div>
    },
  ];

  const data = [];
  collectionAttributes.forEach(attribute => data.push({
    attribute: attribute,
    value: collectionObject[attribute.label]
  }))

  const photoPath = collectionObject.photo_path ? <img src={collectionObject.photo_path}/> : '';

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
    <Divider/>
    <div className="item-image">
    {photoPath}
    </div>

    <p></p>
    <Table columns={columns} dataSource={data}/>
  </div>;

};
