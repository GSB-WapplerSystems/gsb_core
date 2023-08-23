/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
import ContentContainer from"./viewport/content-container";import ConsumerScope from"./event/consumer-scope";import Loader from"./viewport/loader";import NavigationContainer from"./viewport/navigation-container";import Topbar from"./viewport/topbar";class Viewport{constructor(){this.Loader=Loader,this.NavigationContainer=null,this.ContentContainer=null,this.consumerScope=ConsumerScope,this.Topbar=new Topbar,this.NavigationContainer=new NavigationContainer(this.consumerScope),this.ContentContainer=new ContentContainer(this.consumerScope)}}let viewportObject;top.TYPO3.Backend?viewportObject=top.TYPO3.Backend:(viewportObject=new Viewport,top.TYPO3.Backend=viewportObject);export default viewportObject;