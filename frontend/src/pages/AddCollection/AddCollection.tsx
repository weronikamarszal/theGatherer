import {FunctionComponent} from "react";
import {Button, Divider, Form, Input, Select, Space} from "antd";
import {AttributeType} from "../../types/attributeType";
import {useHistory} from "react-router-dom";
import {MinusCircleOutlined, PlusOutlined} from '@ant-design/icons';

const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};

function createCollection(collection, attributes) {
  return fetch('/api/create-collection/', {
    method: 'post',
    body: JSON.stringify(collection),
    headers: {"Content-Type": "application/json"}
  })
    .then(res => res.json())
    .then(res => {
      console.log(res);
      return fetch(`/api/create-attributes/${res.id}`, {
        method: 'post',
        body: JSON.stringify(attributes),
        headers: {"Content-Type": "application/json"}
      })
    })
    .then(res => res.json())
    .then(res => {
      console.log(res);
    })
}

export const AddCollection: FunctionComponent = () => {
  const history = useHistory();
  const onFinish = (values: any) => {
    console.log('Success:', values);
    createCollection(values.collection, values.attributes)
      .then(() => history.push(`/all-collections`))
  };

  return <div>
    <h1>THE GATHERER</h1>
    <Form
      {...layout}
      onFinish={onFinish}
    >

      <Form.Item
        label='Name'
        name={['collection','name']}
        rules={[{required: true}]}
      >
        <Input/>
      </Form.Item>

      <Form.Item
        label='Description'
        name={['collection','description']}
        rules={[{required: true}]}
      >
        <Input/>
      </Form.Item>
      <Form.Item
        label='Private'
        name={['collection','isPrivate']}
        rules={[{required: true}]}
      >
        <Select>
          <Select.Option value={0}>Public</Select.Option>
          <Select.Option value={1}>Private</Select.Option>
        </Select>
      </Form.Item>

      <Divider>Attributes</Divider>

      <Form.List name="attributes">
        {(fields, { add, remove }) => (
          <>
            {fields.map(({ key, name, fieldKey, ...restField }) => (
              <Space key={key} style={{ display: 'flex', marginBottom: 8 }} align="baseline">
                <Form.Item
                  {...restField}
                  label='Name'
                  name={[name, 'label']}
                  fieldKey={[fieldKey, 'label']}
                  rules={[{ required: true}]}
                >
                  <Input/>
                </Form.Item>

                <Form.Item
                  {...restField}
                  label='Type'
                  name={[name, 'type']}
                  fieldKey={[fieldKey, 'type']}
                  rules={[{ required: true}]}
                >
                  <Select>
                    <Select.Option value={AttributeType.STRING}>Text</Select.Option>
                    <Select.Option value={AttributeType.INT}>Integer</Select.Option>
                    <Select.Option value={AttributeType.FLOAT}>Float</Select.Option>
                    <Select.Option value={AttributeType.DATE}>Date</Select.Option>
                  </Select>
                </Form.Item>

                <MinusCircleOutlined onClick={() => remove(name)} />
              </Space>
            ))}
            <Form.Item>
              <Button type="dashed" onClick={() => add()} block icon={<PlusOutlined />}>
                Add field
              </Button>
            </Form.Item>
          </>
        )}
      </Form.List>

      <Form.Item>
        <Button type="primary" htmlType="submit">
          Submit
        </Button>
      </Form.Item>
    </Form>

  </div>;
};


