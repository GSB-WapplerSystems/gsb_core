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
import{AbstractAction}from"./abstract-action";import Icons from"../icons";class DeferredAction extends AbstractAction{async execute(e){return e.dataset.actionLabel=e.innerText,e.classList.add("disabled"),Icons.getIcon("spinner-circle",Icons.sizes.small).then((t=>{e.innerHTML=t})),await this.executeCallback(e)}async executeCallback(e){return await Promise.resolve(this.callback()).finally((()=>{e.innerText=e.dataset.actionLabel,e.classList.remove("disabled")}))}}export default DeferredAction;