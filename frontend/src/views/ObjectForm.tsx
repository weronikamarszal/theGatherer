import {FunctionComponent} from "react";
import {AttributeType} from "../types/attributeType";
import {Button, DatePicker, Form, Input, InputNumber, Upload} from "antd";
import {UploadOutlined} from "@ant-design/icons";


const layout = {
  labelCol: {span: 8},
  wrapperCol: {span: 8},
};
const normFile = (e: any) => {
  if (Array.isArray(e)) {
    return e;
  }
  return e && e.fileList;
};

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


export const ObjectForm: FunctionComponent<{ onFinish: (value) => void, attributes: any[], initValue: any, aboveUpload?: any }> =
  ({
     onFinish,
     attributes,
    initValue,
    aboveUpload
   }) => {
    return (
      <Form
        {...layout}
        onFinish={onFinish}
        initialValues={initValue}
      >

        <Form.Item
          label='name'
          name='name'
        >
          <Input />
        </Form.Item>

        {aboveUpload}
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
          attributes.map(attribute => <AttributeFormItem attribute={attribute}/>)
        }
        <Form.Item>
          <Button type="primary" htmlType="submit">
            Submit
          </Button>
        </Form.Item>
      </Form>
    )
  }

