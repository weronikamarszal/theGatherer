import {Component, FunctionComponent, useEffect, useState} from "react";
import {useHistory, useParams} from "react-router-dom";
import {Button, DatePicker, Form, Input, InputNumber, Upload} from "antd";
import {AttributeType} from "../../types/attributeType";
import {UploadOutlined} from '@ant-design/icons';

const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};

const normFile = (e: any) => {
  console.log('Upload event:', e);
  if (Array.isArray(e)) {
    return e;
  }
  return e && e.fileList;
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
  const history = useHistory();
  const collectionId = Number(params.id)

  const onFinish = (values: any) => {
    let formdata = new FormData();

    formdata.append("collection_id", collectionId.toString());
    formdata.append("name", values.name);
    formdata.append("item_image", values.upload[0].originFileObj, values.upload[0].name);
    collectionAttributes.map((attribute) => ({"id": attribute.id, name: attribute.label, value: null}));
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

      <Form.Item
        name="upload"
        label="Upload"
        valuePropName="fileList"
        getValueFromEvent={normFile}
      >
        <Upload name="logo" maxCount={1} beforeUpload={() => false} listType="picture">
          <Button icon={<UploadOutlined/>}>Click to upload</Button>
        </Upload>
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
