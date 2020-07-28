<?php $render('header', ['loggedUser' => $loggedUser]) ?>
    <section class="container main">
        <?php $render('sidebar',['activeMenu' =>'search']) ?>
        <section class="feed mt-10">
            <h1>Configurações</h1>
            <form action="<?=$base; ?>/config" method="POST" class="form-config">
                <?php if(!empty($flash)):?>
                    <div class='flash'> <?php echo $flash;?></div>
                <?php endif;?> 
                <div class="form-config-file">
                    <label>Novo Avatar:</label><br/>
                    <input type="file" placeholder="Escolher arquivo" name="avatarFile"/><br/><br/>
                    <label>Nova Capa:</label><br>
                    <input type="file" placeholder="Escolher arquivo" name="coverFile"/>
                </div>
                <hr/>
                <div class="form-config-data">
                    <label>Nome Completo</label><br/>
                    <input type="text" name="name" id="name" placeholder="<?=$loggedUser->name;?>"/><br/>
                    <label>Data de nascimento:</label><br/>
                    <input type="text" onfocus="(this.type='date')"  name="birthDate" id="birthDate" placeholder="<?=date('d/m/Y', strtotime($loggedUser->birthDate));  ?>" ><br/>
                    <label>E-mail</label><br/>
                    <input type="email" name="email" id="email" placeholder="<?=$loggedUser->email;?>"/><br/>
                    <label>Cidade:</label><br/>
                    <input type="text" name="city" id="city" placeholder="<?=$loggedUser->city;?>"/><br/>
                    <label>Trabalho:</label><br/>
                    <input type="text" name="work" id="work" placeholder="<?=$loggedUser->work;?>"/><br/>
                    <hr/><br/>
                    <label>Nova Senha:</label><br/>                    
                    <input type="password" name="password" id="password"/><br/>
                    <label>Confirmar Nova Senha:</label><br/>
                    <input type="password" name="newPassword" id="newPassword"/><br/>
                    <input type="submit" class="button form-confir-input-submit" value="Salvar"/>
                </div>
            </form>
        </section>
    </section>
 <?php $render('footer')?>; 
 </body>
</html>