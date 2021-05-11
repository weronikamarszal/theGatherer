import {FunctionComponent, useEffect, useState} from "react";
import {useHistory, useParams} from "react-router-dom";
import {AttributeFormItem, ObjectForm} from "../../views/ObjectForm";
import {Button, Form, Input, Select, Upload} from "antd";
import {UploadOutlined} from "@ant-design/icons";

const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};

function updateCollection(collection, collectionId) {
  return fetch(`/api/update-collection/${collectionId}`, {
    method: 'PATCH',
    body: JSON.stringify(collection),
    headers: {"Content-Type": "application/json"},
  })
    .then(res => res.json())
}


export const EditCollection: FunctionComponent = () => {
  let params = useParams<any>();
  const history = useHistory();
  const collectionId = Number(params.id)

  const onFinish = (values: any) => {
    console.log('Success:', values);
    updateCollection(values, collectionId)
      .then(() => history.push(`/all-collections`))
  };

  const [collection, setCollection] = useState<any>(null);
  useEffect(() => {
    fetch(`/api/get-collections/${collectionId}`)
      .then(res => res.json())
      .then(res => {
        setCollection(res)
      })
  }, [setCollection]);
  console.log(collection)

  return <div>
    <h1>Edit collection</h1>
    {collection &&
    <Form
      {...layout}
      onFinish={onFinish}
      initialValues={collection}
    >

        <Form.Item
            label='Name'
            name={['name']}
            rules={[{required: true}]}
        >
            <Input/>
        </Form.Item>

        <Form.Item
            label='Description'
            name={['description']}
            rules={[{required: true}]}
        >
            <Input/>
        </Form.Item>
        <Form.Item
            label='Private'
            name={['isPrivate']}
            rules={[{required: true}]}
        >
            <Select>
                <Select.Option value={0}>Public</Select.Option>
                <Select.Option value={1}>Private</Select.Option>
            </Select>
        </Form.Item>

        <Form.Item>
            <Button type="primary" htmlType="submit">
                Submit
            </Button>
        </Form.Item>
    </Form>
    }
  </div>;
}
