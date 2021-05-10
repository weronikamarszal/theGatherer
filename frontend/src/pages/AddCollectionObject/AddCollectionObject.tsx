import {Component, FunctionComponent, useEffect, useState} from "react";
import {useHistory, useParams} from "react-router-dom";
import {Button, DatePicker, Form, Input, InputNumber, Upload} from "antd";
import {AttributeType} from "../../types/attributeType";
import {UploadOutlined} from '@ant-design/icons';
import {ObjectForm} from "../../views/ObjectForm";

function getAttributes(collectionId: number) {
  const apiUrl = `/api/get-collection-attributes/${collectionId}`;
  return fetch(apiUrl)
    .then(res => res.json())
}

export const AddCollectionObject: FunctionComponent = () => {
  let params = useParams<any>();
  const history = useHistory();
  const collectionId = Number(params.id)

  const onFinish = (values: any) => {
    let formdata = new FormData();

    formdata.append("collection_id", collectionId.toString());
    formdata.append("name", values.name);
    if(values?.upload?.[0]) {
      formdata.append("item_image", values.upload[0].originFileObj, values.upload[0].name);
    }
    formdata.append("attributes", JSON.stringify(collectionAttributes.map((attribute) => ({
      id: attribute.id,
      name: attribute.label,
      value: values[attribute.label],
    }))));
    console.log(values);

    fetch('/api/add-object', {
      method: 'POST',
      body: formdata,
      redirect: 'follow',
    })
      .then(res => res.json())
      .then(console.log)
      .then(() => history.push(`/collection/${collectionId}`))


  };

  const [collectionAttributes, setCollectionAttributes] = useState<any[]>([]);
  useEffect(() => {
    getAttributes(collectionId)
      .then(res => {
        setCollectionAttributes(res)
      })
  }, [setCollectionAttributes]);
  return <div>
    <h1>Add your item</h1>
    <ObjectForm onFinish={onFinish} attributes={collectionAttributes} initValue={null} />
  </div>;
};
