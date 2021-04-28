import {FunctionComponent, useEffect, useState} from "react";
import {Button, Checkbox, Divider, Form, Input, Select} from "antd";
import {AttributeFormItem} from "../AddCollectionObject/AddCollectionObject";
import {AttributeType} from "../../types/attributeType";

const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};

function createCollection(collection, attributes) {
  fetch('/api/create-collection/', {
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
  const onFinish = (values: any) => {
    console.log('Success:', values);
    createCollection(values.collection, values.attribute)
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
      <Form.Item
        label='Name'
        name={['attribute','label']}
        rules={[{required: true}]}
      >
        <Input/>
      </Form.Item>
      <Form.Item
        label='Type'
        name={['attribute','type']}
        rules={[{required: true}]}
      >
        <Select>
          <Select.Option value={AttributeType.STRING}>Text</Select.Option>
          <Select.Option value={AttributeType.INT}>Integer</Select.Option>
          <Select.Option value={AttributeType.FLOAT}>Float</Select.Option>
          <Select.Option value={AttributeType.DATE}>Date</Select.Option>
        </Select>
      </Form.Item>

      <Form.Item>
        <Button type="primary" htmlType="submit">
          Submit
        </Button>
      </Form.Item>
    </Form>

  </div>;
};


