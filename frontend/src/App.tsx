import React from 'react';
import './App.css';

import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";

import {Col, Menu, Row} from 'antd';
import {AllCollections} from "./pages/AllCollections/AllCollections";
import {MyCollections} from "./pages/MyCollections/MyCollections";
import {Collection} from './pages/Collection/Collection';
import {CollectionObject} from "./pages/CollectionObject/CollectionObject";
import {AddCollectionObject} from "./pages/AddCollectionObject/AddCollectionObject";
import {AddCollection} from "./pages/AddCollection/AddCollection";
import {EditObject} from "./pages/EditObject/EditObject";

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
          <Col>
            <Sider/>
          </Col>
          <Col flex="1" className={'app-content'}>
            <Switch>
              <Route path="/all-collections">
                <AllCollections/>
              </Route>
              <Route path="/my-collections">
                <MyCollections/>
              </Route>
              <Route path="/collection/:id">
                <Collection/>
              </Route>
              <Route path="/object/:id">
                <CollectionObject/>
              </Route>
              <Route path="/:id/add-object">
                <AddCollectionObject/>
              </Route>
              <Route path="/add-collection">
                <AddCollection/>
              </Route>
              <Route path="/edit-object/:id">
                <EditObject/>
              </Route>
            </Switch>
          </Col>
        </Row>
      </div>
    </Router>
  )
}
