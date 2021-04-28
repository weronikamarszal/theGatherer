import {FunctionComponent, useEffect, useState} from "react";
import {useParams} from "react-router-dom";
import {Button, DatePicker, Form, Input, InputNumber} from "antd";
import {AttributeType} from "../../types/attributeType";

const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};

function getAttributes(collectionId: number) {
  const apiUrl = `/api/get-collection-attributes/${collectionId}`;
  return fetch(apiUrl)
    .then(res => res.json())
}

export const AttributeFormItem: FunctionComponent<{ attribute: any }> = ({attribute}) => {
  switch (attribute.type) {
    case AttributeType.STRING :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <Input/>
      </Form.Item>
    case AttributeType.FLOAT :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <InputNumber/>
      </Form.Item>
    case AttributeType.INT :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <InputNumber/>
      </Form.Item>
    case AttributeType.DATE :
      return <Form.Item label={attribute.label}
                        name={attribute.label}>
        <DatePicker/>
      </Form.Item>
    default:
      return <>NULL</>
  }
}

export const AddCollectionObject: FunctionComponent = () => {
  let params = useParams<any>();
  const collectionId = Number(params.id)

  const onFinish = (values: any) => {
    values.collection_id = collectionId;
    values.photo_path = "NULL"
    console.log('Success:', values);
    fetch('/api/add-object', {method: 'post', body: JSON.stringify(values), headers: {"Content-Type": "application/json"}})
      .then(res => res.json())
      .then(console.log)
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

    <Form
      {...layout}
      onFinish={onFinish}
    >

      <Form.Item
        label='name'
        name='name'
      >
        <Input/>
      </Form.Item>
      {
        collectionAttributes.map(attribute => <AttributeFormItem attribute={attribute}/>)
      }
      <Form.Item>
        <Button type="primary" htmlType="submit">
          Submit
        </Button>
      </Form.Item>
    </Form>


  </div>;
};
