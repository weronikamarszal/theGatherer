import React from 'react';
import './App.css';

import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";

import {Col, Menu, Row} from 'antd';
import {AppstoreOutlined, MailOutlined, SettingOutlined} from '@ant-design/icons';
import {CollectionsList} from "./views/CollectionsList";
import {AllCollections} from "./pages/AllCollections/AllCollections";
import {MyCollections} from "./pages/MyCollections/MyCollections";
import ThisCollection from './pages/ThisCollection/ThisCollection';
import {CollectionObject} from "./pages/CollectionObject/CollectionObject";

const {SubMenu} = Menu;

class Sider extends React.Component {

  render() {
    return (
      <Menu
        style={{
          width: 256,
          height: '100vh'
        }}
        mode="inline"
        theme="dark"
      >
        <Menu.ItemGroup key="g1" title="Welcome, name">
          <Menu.Item key="1">Sign out</Menu.Item>
        </Menu.ItemGroup>
        <Menu.ItemGroup key="g2">
          <Menu.Item key="2">
            <Link to="/all-collections">All collections</Link>
          </Menu.Item>
          <Menu.Item key="3">
            <Link to="/my-collections">My collection</Link>
          </Menu.Item>
          <Menu.Item key="4">
            <Link to="/this-collection">This Collection</Link>
          </Menu.Item>
          <Menu.Item key="5">
            <Link to="/object/1">Object</Link>
          </Menu.Item>
        </Menu.ItemGroup>
      </Menu>


    );
  }
}


export default function App() {
  return (
    <Router>

      <div className="App">
        <Row>
          <Col flex="100px">
            <Sider/>
          </Col>
          <Col flex="auto" className={'app-content'}>
            <Switch>
              <Route path="/all-collections">
                <AllCollections/>
              </Route>
              <Route path="/my-collections">
                <MyCollections/>
              </Route>
              <Route path="/this-collection">
                <ThisCollection/>
              </Route>
              <Route path="/object/:id">
                <CollectionObject/>
              </Route>
            </Switch>
          </Col>
        </Row>
      </div>
    </Router>
  )
}
